# Output variables for AWS Terraform configuration
# U-Cup Tournament Deployment

output "ec2_instance_id" {
  description = "ID of the EC2 instance"
  value       = aws_instance.u_cup_app.id
}

output "ec2_public_ip" {
  description = "Public IP address of the EC2 instance"
  value       = aws_instance.u_cup_app.public_ip
}

output "ec2_public_dns" {
  description = "Public DNS name of the EC2 instance"
  value       = aws_instance.u_cup_app.public_dns
}

output "rds_endpoint" {
  description = "RDS instance endpoint"
  value       = aws_db_instance.u_cup_db.endpoint
}

output "rds_database_name" {
  description = "Database name"
  value       = aws_db_instance.u_cup_db.db_name
}

output "s3_bucket_name" {
  description = "S3 bucket name for storage"
  value       = aws_s3_bucket.u_cup_storage.bucket
}

output "s3_bucket_arn" {
  description = "S3 bucket ARN"
  value       = aws_s3_bucket.u_cup_storage.arn
}

output "vpc_id" {
  description = "VPC ID"
  value       = aws_vpc.u_cup_vpc.id
}

output "security_group_id" {
  description = "EC2 Security Group ID"
  value       = aws_security_group.u_cup_ec2_sg.id
}

output "website_url" {
  description = "URL to access the website"
  value       = "http://${aws_instance.u_cup_app.public_ip}"
}

output "ssh_connection_string" {
  description = "SSH connection string"
  value       = "ssh -i ${var.ssh_public_key_path} ubuntu@${aws_instance.u_cup_app.public_ip}"
}