# Job Card System - User Guide

## Overview
The Job Card system provides a professional, downloadable PDF for tracking job/service details with customer information, work performed, and signatures.

## Features

### ✨ Professional Design
- Modern, clean layout with gradient header
- Color-coded status badges
- Shop branding with logo/details
- A4 size for easy printing

### 📋 Comprehensive Information
- **Shop Details**: Name, address, phone, email
- **Job Reference**: Unique job identifier (e.g., APFJS00001)
- **Customer Information**: Name, phone, address
- **Job Details**: Type, status, estimated duration, description
- **Work Notes Section**: Space for technician notes
- **Signature Areas**: For both technician and customer

### 🎨 Status Badges
- **Pending** - Yellow badge
- **In Progress** - Blue badge
- **Completed** - Green badge
- **On Hold** - Red badge
- **Cancelled** - Gray badge

## How to Use

### 1. Create a Job
Navigate to: **Jobs > Create New Job**
- Enter customer details
- Add job type and description
- Set estimated duration
- Save the job

### 2. Download Job Card PDF
Three ways to download:

**Option A: From Job Detail Page**
1. Go to **Jobs** menu
2. Click on any job to view details
3. Click **"Download PDF"** button at the top

**Option B: From Job List**
1. Go to **Jobs List**
2. Find the job in the table
3. Click the **PDF icon** in the Actions column

**Option C: From Jobs Index**
1. Go to **Jobs** page
2. Find the job in the list
3. Click the **PDF download button**

### 3. Print or Share
- The PDF downloads automatically
- Open the PDF and print it
- Or email it to customers

## Job Card Contents

### Header Section
- **Shop Name** (in large, bold text)
- Shop Address
- Shop Phone Number
- Shop Email

### Job Reference Box
- Job Reference Number (e.g., APFJS00001)
- Creation Date & Time

### Customer Information Section
- Customer Name
- Phone Number
- Address (if provided)

### Job Details Section
- **Job Type**: Service category
- **Status**: Current job status with color badge
- **Estimated Duration**: Expected completion time
- **Date Created**: When job was created
- **Description**: Detailed job description

### Work Performed Section
- Large text area for technician notes
- Space to document work completed
- Parts/materials used

### Signature Section
Two boxes for:
1. **Technician Signature**
   - Signature line
   - Name field
   - Date field

2. **Customer Acceptance**
   - Signature line
   - Name field
   - Date field

### Footer
- "Computer-generated document" notice
- Generation timestamp

## Customization

### Shop Details
Update your shop information in:
**Settings > Shop Settings**

- Shop Name
- Address
- Phone
- Email

These details automatically appear on all job cards.

### Job Types
Create custom job types:
**Settings > Job Types**

Examples:
- Repair Service
- Maintenance
- Installation
- Inspection
- Custom Service

## Tips & Best Practices

### ✅ Do's
- Fill in all customer details for complete records
- Add detailed job descriptions
- Update job status regularly
- Download PDF before job completion
- Print and have customer sign upon completion

### ❌ Don'ts
- Don't leave customer information blank
- Don't forget to update job status
- Don't skip the description field

## Technical Details

- **PDF Size**: A4 (210mm x 297mm)
- **Format**: Portrait orientation
- **Font**: DejaVu Sans (supports special characters)
- **File Naming**: `JobCard_[Reference]_[Date].pdf`
- **Example**: `JobCard_APFJS00001_2025-12-25.pdf`

## Troubleshooting

### PDF won't download
- Check your browser's pop-up blocker
- Ensure you have permission to download files
- Try a different browser

### Missing information on PDF
- Update shop settings
- Ensure customer details are filled
- Refresh and try again

### Poor print quality
- Use "Print to PDF" at 100% scale
- Check printer settings
- Ensure paper size is set to A4

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Contact system administrator
- Review this guide

---

**Last Updated**: December 25, 2025
**Version**: 2.0
**System**: NexoraLabs Job Management
