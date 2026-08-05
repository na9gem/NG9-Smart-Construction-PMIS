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

# Progress Report

| Field | Type | Description |
|------|------|-------------|
| id | bigint | Primary Key |
| project_id | foreignId | อ้างอิงโครงการ |
| contract_id | foreignId | อ้างอิงสัญญา |
| report_date | date | วันที่รายงาน |
| progress_percent | decimal(5,2) | ความก้าวหน้าสะสม (%) |
| work_description | text | รายละเอียดงานที่ดำเนินการ |
| problem | text | ปัญหา/อุปสรรค |
| solution | text | แนวทางแก้ไข |
| weather | string | สภาพอากาศ |
| manpower | integer | จำนวนแรงงาน |
| status | string | Draft / Submitted / Approved |
| created_at | timestamp | วันที่สร้าง |
| updated_at | timestamp | วันที่แก้ไข |

# Site Photos

| Field | Type | Description |
|------|------|-------------|
| id | bigint | Primary Key |
| progress_report_id | foreignId | อ้างอิงรายงานความก้าวหน้า |
| photo_type | string | Before / During / After / Issue |
| photo_name | string | ชื่อรูปภาพ |
| file_path | string | ที่เก็บไฟล์ |
| description | text | คำอธิบายรูป |
| taken_at | datetime | วันที่ถ่าย |
| latitude | decimal(10,7) | ละติจูด |
| longitude | decimal(10,7) | ลองจิจูด |
| created_at | timestamp | วันที่สร้าง |
| updated_at | timestamp | วันที่แก้ไข |

# Inspection

| Field | Type | Description |
|------|------|-------------|
| id | bigint | Primary Key |
| project_id | foreignId | อ้างอิงโครงการ |
| contract_id | foreignId | อ้างอิงสัญญา |
| inspection_date | date | วันที่ตรวจ |
| inspection_type | string | Quality / Safety / Material / Progress |
| location | string | ตำแหน่งที่ตรวจ |
| result | string | Pass / Fail |
| remark | text | ข้อสังเกต |
| corrective_action | text | แนวทางแก้ไข |
| due_date | date | กำหนดแก้ไข |
| status | string | Open / Closed |
| created_at | timestamp | วันที่สร้าง |
| updated_at | timestamp | วันที่แก้ไข |

