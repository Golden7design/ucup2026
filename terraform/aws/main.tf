# Terraform configuration for AWS Free Tier deployment
# U-Cup Tournament - Elmish Moukouanga

tierraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 4.0"
    }
  }
}

provider "aws" {
  region = var.aws_region
}

# Create VPC for our application
resource "aws_vpc" "u_cup_vpc" {
  cidr_block           = "10.0.0.0/16"
  enable_dns_support   = true
  enable_dns_hostnames = true
  
  tags = {
    Name = "U-Cup-Tournament-VPC"
  }
}

# Create Internet Gateway
resource "aws_internet_gateway" "u_cup_igw" {
  vpc_id = aws_vpc.u_cup_vpc.id
  
  tags = {
    Name = "U-Cup-Tournament-IGW"
  }
}

# Create Public Subnet
resource "aws_subnet" "u_cup_public_subnet" {
  vpc_id                  = aws_vpc.u_cup_vpc.id
  cidr_block              = "10.0.1.0/24"
  availability_zone       = "${var.aws_region}a"
  map_public_ip_on_launch = true
  
  tags = {
    Name = "U-Cup-Tournament-Public-Subnet"
  }
}

# Create Route Table
resource "aws_route_table" "u_cup_route_table" {
  vpc_id = aws_vpc.u_cup_vpc.id
  
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.u_cup_igw.id
  }
  
  tags = {
    Name = "U-Cup-Tournament-Route-Table"
  }
}

# Associate Route Table with Subnet
resource "aws_route_table_association" "u_cup_route_association" {
  subnet_id      = aws_subnet.u_cup_public_subnet.id
  route_table_id = aws_route_table.u_cup_route_table.id
}

# Create Security Group for EC2
resource "aws_security_group" "u_cup_ec2_sg" {
  name        = "u-cup-ec2-security-group"
  description = "Security group for U-Cup Tournament EC2 instance"
  vpc_id      = aws_vpc.u_cup_vpc.id
  
  ingress {
    description = "HTTP"
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  
  ingress {
    description = "HTTPS"
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  
  ingress {
    description = "SSH"
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
  
  tags = {
    Name = "U-Cup-Tournament-EC2-SG"
  }
}

# Create EC2 Instance (Free Tier eligible)
resource "aws_instance" "u_cup_app" {
  ami                         = var.ami_id
  instance_type               = "t2.micro"
  subnet_id                   = aws_subnet.u_cup_public_subnet.id
  vpc_security_group_ids      = [aws_security_group.u_cup_ec2_sg.id]
  associate_public_ip_address = true
  key_name                    = aws_key_pair.u_cup_key_pair.key_name
  
  root_block_device {
    volume_size = 8
    volume_type = "gp2"
  }
  
  tags = {
    Name = "U-Cup-Tournament-App"
  }
  
  user_data = <<-EOF
              #!/bin/bash
              sudo apt-get update -y
              sudo apt-get install -y docker.io docker-compose git unzip
              sudo systemctl enable docker
              sudo systemctl start docker
              sudo usermod -aG docker ubuntu
              EOF
}

# Create RDS MySQL Instance (Free Tier eligible)
resource "aws_db_instance" "u_cup_db" {
  allocated_storage      = 20
  engine                 = "mysql"
  engine_version         = "8.0"
  instance_class         = "db.t2.micro"
  db_name                = var.db_name
  username               = var.db_username
  password               = var.db_password
  parameter_group_name   = "default.mysql8.0"
  skip_final_snapshot    = true
  publicly_accessible    = false
  vpc_security_group_ids = [aws_security_group.u_cup_rds_sg.id]
  db_subnet_group_name   = aws_db_subnet_group.u_cup_db_subnet_group.name
}

# Security Group for RDS
resource "aws_security_group" "u_cup_rds_sg" {
  name        = "u-cup-rds-security-group"
  description = "Security group for U-Cup Tournament RDS instance"
  vpc_id      = aws_vpc.u_cup_vpc.id
  
  ingress {
    description     = "MySQL"
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.u_cup_ec2_sg.id]
  }
  
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
  
  tags = {
    Name = "U-Cup-Tournament-RDS-SG"
  }
}

# DB Subnet Group
resource "aws_db_subnet_group" "u_cup_db_subnet_group" {
  name       = "u-cup-db-subnet-group"
  subnet_ids = [aws_subnet.u_cup_public_subnet.id]
  
  tags = {
    Name = "U-Cup-Tournament-DB-Subnet-Group"
  }
}

# Create S3 Bucket for storage
resource "aws_s3_bucket" "u_cup_storage" {
  bucket = "u-cup-tournament-${random_id.bucket_suffix.hex}"
  
  tags = {
    Name = "U-Cup-Tournament-Storage"
  }
}

# S3 Bucket Policy for public access (adjust as needed)
resource "aws_s3_bucket_policy" "u_cup_bucket_policy" {
  bucket = aws_s3_bucket.u_cup_storage.id
  
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Sid       = "PublicReadGetObject"
        Effect    = "Allow"
        Principal = "*"
        Action    = "s3:GetObject"
        Resource   = "${aws_s3_bucket.u_cup_storage.arn}/*"
      }
    ]
  })
}

# Create Key Pair for SSH access
resource "aws_key_pair" "u_cup_key_pair" {
  key_name   = "u-cup-tournament-key"
  public_key = file(var.ssh_public_key_path)
}

# Random suffix for S3 bucket
resource "random_id" "bucket_suffix" {
  byte_length = 4
}

# Output useful information
output "ec2_public_ip" {
  value = aws_instance.u_cup_app.public_ip
}

output "rds_endpoint" {
  value = aws_db_instance.u_cup_db.endpoint
}

output "s3_bucket_name" {
  value = aws_s3_bucket.u_cup_storage.bucket
}

output "website_url" {
  value = "http://${aws_instance.u_cup_app.public_ip}"
}