# NG9 Data Dictionary

## Module : Project

| Field | Type | Required | Description |
|------|------|:--------:|-------------|
| id | bigint | ✓ | Primary Key |
| project_code | varchar(50) | ✓ | รหัสโครงการ |
| project_name | varchar(255) | ✓ | ชื่อโครงการ |
| owner_name | varchar(255) | ✓ | หน่วยงานเจ้าของโครงการ |
| contractor_name | varchar(255) | | ผู้รับจ้าง |
| consultant_name | varchar(255) | | ผู้ควบคุมงาน |
| contract_number | varchar(100) | | เลขที่สัญญาหลัก |
| budget | decimal(15,2) | ✓ | วงเงินโครงการ |
| start_date | date | ✓ | วันที่เริ่มต้น |
| finish_date | date | ✓ | วันที่สิ้นสุดตามสัญญา |
| actual_finish_date | date | | วันที่แล้วเสร็จจริง |
| progress_percent | decimal(5,2) | | ความก้าวหน้าปัจจุบัน (%) |
| status | enum | ✓ | Draft / Active / Completed / Cancelled |
| created_at | timestamp | ✓ | วันที่สร้างข้อมูล |
| updated_at | timestamp | ✓ | วันที่แก้ไขล่าสุด |