# Variables for AWS Terraform configuration
# U-Cup Tournament Deployment

variable "aws_region" {
  description = "AWS region to deploy resources"
  type        = string
  default     = "us-east-1"
}

variable "ami_id" {
  description = "AMI ID for EC2 instance (Ubuntu 20.04 LTS)"
  type        = string
  default     = "ami-0c55b159cbfafe1f0" # Ubuntu 20.04 LTS in us-east-1
}

variable "db_name" {
  description = "Database name for RDS"
  type        = string
  default     = "u_cup_tournament"
}

variable "db_username" {
  description = "Database username"
  type        = string
  default     = "ucupadmin"
  sensitive   = true
}

variable "db_password" {
  description = "Database password"
  type        = string
  sensitive   = true
}

variable "ssh_public_key_path" {
  description = "Path to SSH public key"
  type        = string
  default     = "~/.ssh/id_rsa.pub"
}

variable "instance_type" {
  description = "EC2 instance type"
  type        = string
  default     = "t2.micro"
}

variable "environment" {
  description = "Deployment environment"
  type        = string
  default     = "production"
}