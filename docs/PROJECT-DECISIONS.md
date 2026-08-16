# NG9 Project Decision Register

> Project: NG9 Smart Construction PMIS
>
> Purpose: Central register for important system and business decisions.
>
> Date Established: 2026-08-12

---

## Decision Status

- PROPOSED = ข้อเสนอเพื่อพิจารณา
- APPROVED = ผู้รับผิดชอบโครงการยืนยันแล้ว
- LOCKED = ใช้เป็น Baseline และห้ามเปลี่ยนโดยพลการ
- SUPERSEDED = ถูกแทนที่ด้วย Decision ใหม่

---

# PD-001 — Project / Contract Cardinality

**Status:** APPROVED

## Decision

NG9 V1 กำหนด:

> 1 Project = 1 Contract

## Rationale

Project เป็นหน่วยหลักในการบริหารและติดตามโครงการก่อสร้างของ NG9 V1

## Impact

- Project และ Contract ต้องมีความสัมพันธ์แบบ 1:1
- Database ต้องสามารถ enforce Business Rule นี้ได้
- API ต้องป้องกันการสร้าง Contract ซ้ำสำหรับ Project เดียว
- Frontend ต้องออกแบบตามความสัมพันธ์นี้

## Implementation Status

Business Decision ถูกกำหนดแล้ว แต่ Database Enforcement ยังไม่ดำเนินการ

---

# PD-002 — RBAC Source of Truth

**Status:** APPROVED

## Proposal

ใช้ Spatie Role / Permission เป็น Authorization Source of Truth ของ NG9

โครงสร้างหลัก:

User
→ Role
→ Permission

## Existing Evidence

ระบบปัจจุบันมีทั้ง:

- users.role
- Spatie HasRoles
- roles
- permissions
- model_has_roles
- model_has_permissions
- role_has_permissions

## Proposed Rule

`users.role` จะยังคงอยู่ชั่วคราวเพื่อ Compatibility และยังไม่ถูกลบ

Authorization หลักควรใช้ Spatie Role / Permission

## Open Point

ต้องกำหนด Transition Plan ก่อน Deprecate หรือยกเลิกการใช้งาน `users.role`

---

# PD-003 — Progress Plan Versioning

**Status:** APPROVED

## Existing Evidence

Progress Plan มี:

- plan_type
- version
- effective_date
- source_document_id
- is_baseline
- status

และมี Unique Constraint:

contract_id + plan_type + version

## Proposal

Progress Plan ต้องรองรับ Versioning และสามารถระบุ Baseline ได้

Baseline เดิมต้องสามารถตรวจสอบย้อนหลังได้ และไม่ควรถูกแก้ไขทับโดยไม่มีประวัติ

## Open Points

ต้องกำหนด:

- ประเภทของ Plan
- Versioning Rule
- Baseline Selection Rule
- Baseline Change Rule

---
## Decision

Progress Plan ของแต่ละ Contract สามารถมีได้หลาย Version

รูปแบบ Versioning ของ NG9 V1:

Baseline / 01
Revision / 02
Revision / 03
...

## Business Rules

1. Progress Plan ต้องผูกกับ Contract
2. แต่ละ Plan ต้องระบุ `plan_type` และ `version`
3. `contract_id + plan_type + version` ต้องไม่ซ้ำกัน
4. หนึ่ง Contract มี Active Baseline ได้เพียงหนึ่งชุด
5. Baseline ใช้เป็นแผนอ้างอิงหลักของ Contract
6. เมื่อมีการเปลี่ยนแผนที่ต้องเก็บประวัติ ให้สร้าง Progress Plan Version ใหม่
7. ไม่แก้ทับข้อมูลของ Plan Version ที่ใช้เป็นประวัติ
8. Progress Plan Item ต้องผูกกับ Progress Plan Version โดยตรง
9. Baseline หรือ Revision เดิมต้องสามารถตรวจสอบย้อนหลังได้
10. การเปลี่ยน Baseline ต้องไม่ทำให้ข้อมูล Baseline เดิมสูญหาย

## Implementation Status

Database ปัจจุบันรองรับ Version ด้วย `plan_type + version` และมี Unique Constraint ที่:

`contract_id + plan_type + version`

อย่างไรก็ตาม Database ยังไม่ได้ enforce ว่าหนึ่ง Contract มี Active Baseline ได้เพียงหนึ่งชุด

การปรับ Database Enforcement จะทำในขั้น Design/Development ถัดไป

# PD-004 — S-Curve / Cumulative Progress Calculation

**Status:** APPROVED

## Existing Evidence

Progress Plan Item มี:

- plan_date
- planned_percent
- planned_weight
- cumulative_percent

## Proposal

Cumulative Planned Progress ควรเป็นค่าที่ระบบคำนวณจากข้อมูลแผน แทนการให้ผู้ใช้กรอกโดยอิสระ

แนวคิดเบื้องต้น:

Activity Weight
+
Progress Plan Item
→ Planned Weighted Progress
→ Cumulative Planned Progress
→ S-Curve

## Open Points

ต้องกำหนดสูตรการคำนวณอย่างเป็นทางการก่อน Lock

---
## Decision

NG9 V1 ใช้ระบบคำนวณ Planned S-Curve จาก Activity Weight และ Progress Plan Item โดยให้ระบบเป็นผู้คำนวณค่าที่เกี่ยวข้องกับ Cumulative Progress

## Calculation Rules

1. `Activity.weight` เป็น Source of Truth ของน้ำหนัก Activity ใน Progress Plan
2. น้ำหนัก Activity ของ Progress Plan เดียวกันต้องรวมกันเป็น 100%
3. `planned_percent` หมายถึง Planned Incremental Progress ของ Activity ในแต่ละ `plan_date`
4. Planned Weighted Progress ของแต่ละรายการคำนวณจาก:

   `Activity.weight × planned_percent ÷ 100`

5. Daily Planned Progress คำนวณจากผลรวม Planned Weighted Progress ของรายการที่มี `plan_date` เดียวกัน
6. Cumulative Planned Progress คำนวณจากผลรวม Daily Planned Progress ตามลำดับวันที่
7. Planned S-Curve ใช้ Cumulative Planned Progress เป็นค่าหลัก
8. `planned_weight` ไม่ควรเป็นค่าที่ผู้ใช้กำหนดอย่างอิสระ และในขั้น Development ให้ระบบคำนวณจาก `Activity.weight`
9. `cumulative_percent` ไม่ควรเป็นค่าที่ผู้ใช้กำหนดอย่างอิสระ และในขั้น Development ให้ระบบคำนวณจาก Planned Progress
10. Actual S-Curve ใช้เฉพาะ Progress Report ที่มีสถานะ `Approved` และใช้ `progress_percent` ตาม `report_date`
11. Actual Current Progress ใช้ Progress Report ที่ `Approved` ล่าสุด
12. Planned และ Actual ต้องแยกจากกันอย่างชัดเจนเพื่อรองรับการตรวจสอบย้อนหลัง

## Current Implementation Evidence

ปัจจุบัน `ProgressCalculationService` คำนวณ Planned S-Curve โดยใช้:

`Activity.weight × planned_percent ÷ 100`

โดย `Activity.weight` เป็น Source of Truth ของน้ำหนัก Activity
และ `planned_percent` เป็น Planned Incremental Progress ของ Activity
ในแต่ละ `plan_date`

ระบบจะคำนวณ Planned Weighted Progress ของแต่ละรายการ
จากนั้นรวมเป็น Daily Planned Progress และสะสมตามลำดับวันที่
เพื่อสร้าง Cumulative Planned Progress และ Planned S-Curve

`ProgressPlanItemRequest` ไม่รับ `planned_weight`
และ `cumulative_percent` จากผู้ใช้

สำหรับ Validation ของ `planned_percent`
ระบบตรวจสอบผลรวมของ Activity เดียวกันภายใน
Progress Plan Version เดียวกัน และไม่อนุญาตให้เกิน 100%
โดยใช้ทั้ง Create และ Update

## Implementation Status

Decision ได้รับการอนุมัติแล้ว

PD-004 Derived Calculation ได้รับการ Implement แล้ว

Implementation ครอบคลุม:

- Activity.weight เป็น Source of Truth
- Planned Weighted Progress
- Cumulative Planned Progress
- Planned S-Curve
- Actual Progress จาก Approved Progress Report
- Actual S-Curve
- Validation ของ Planned Incremental Progress

Validation ของ Progress Plan Item ผ่านการทดสอบ
ทั้ง Create และ Update โดยกรณีที่ผลรวมเกิน 100%
ระบบตอบ HTTP 422 และไม่เปลี่ยนแปลงข้อมูลเดิมใน Database

Database ไม่จัดเก็บ `planned_weight`
และ `cumulative_percent` ใน `progress_plan_items`
## Implementation Design Decision

**Selected Option:** B — Derived Calculation

NG9 V1 จะไม่จัดเก็บ `planned_weight` และ `cumulative_percent` ใน `progress_plan_items`

### Source of Truth

`activities.weight` เป็น Source of Truth สำหรับน้ำหนักของ Activity

### Calculation Flow

Activity.weight
→ ProgressPlanItem.planned_percent
→ ProgressCalculationService
→ Planned Weighted Progress
→ Cumulative Planned Progress
→ Planned S-Curve

### Validation Rule — Planned Incremental Progress

สำหรับ Activity เดียวกันภายใน Progress Plan Version เดียวกัน
ผลรวมของ `planned_percent` จาก `ProgressPlanItem` ทุกแถว
ต้องไม่เกิน 100%

#### Business Rule

1. ผลรวม `planned_percent` ของ Activity เดียวกันใน Progress Plan Version เดียวกันต้องไม่เกิน 100%
2. Rule นี้ใช้กับการสร้าง (Create) และแก้ไข (Update) Progress Plan Item
3. การตรวจสอบต้องพิจารณาเฉพาะรายการที่มี `activity_id` และ `progress_plan_id` เดียวกัน
4. กรณี Update ต้องไม่นับค่าเดิมของรายการที่กำลังแก้ซ้ำ
5. หากผลรวมหลังการ Create หรือ Update มากกว่า 100% ให้ระบบปฏิเสธรายการ
6. การปฏิเสธใช้ HTTP `422 Unprocessable Entity`
7. การลบ Progress Plan Item ไม่ต้องตรวจ Rule นี้
8. `ProgressCalculationService` มีหน้าที่คำนวณ Progress และ S-Curve ไม่ใช่หน้าที่หลักในการรับผิดชอบ Validation
9. Rule นี้มีวัตถุประสงค์เพื่อป้องกัน Planned Cumulative Progress เกิน 100% จากข้อมูลต้นทาง

#### Validation Examples

- `70 + 30 = 100%` → PASS
- `70 + 20 = 90%` → PASS
- `70 + 50 = 120%` → FAIL
- `100%` → PASS
- `100 + 1 = 101%` → FAIL

### Database Design

`progress_plan_items` จะเก็บเฉพาะข้อมูลต้นทางที่จำเป็นต่อการคำนวณ:

- progress_plan_id
- activity_id
- plan_date
- planned_percent

ไม่จัดเก็บค่าที่สามารถคำนวณซ้ำได้จากข้อมูลต้นทาง:

- planned_weight
- cumulative_percent

### Rationale

1. ลดข้อมูลซ้ำ
2. ป้องกัน Activity Weight และ Planned Weight ไม่ตรงกัน
3. ป้องกันการกรอก Cumulative Progress ไม่ถูกต้อง
4. ให้มี Single Source of Calculation
5. เหมาะสมกับ NG9 V1 ที่ต้องการโครงสร้างไม่ซับซ้อน
6. สามารถตรวจสอบย้อนกลับได้จาก Activity Weight และ Progress Plan Item

### Implementation Impact

ต้องปรับ:

- Progress Plan Item Migration
- ProgressPlanItem Model
- ProgressPlanItemRequest
- ProgressPlanItemController
- ProgressCalculationService

และต้องตรวจสอบ Test ที่เกี่ยวข้องก่อน Commit

**Approved Implementation Decision Date:** 2026-08-12
# PD-005 — Codespaces Cost Control

**Status:** LOCKED

**Locked Date:** 2026-08-12

## Decision

NG9 ต้องควบคุมค่าใช้จ่าย GitHub Codespaces เป็น Project Constraint

## Rules

1. ใช้ Codespaces เท่าที่จำเป็น
2. เมื่อเลิกทำงานให้ Stop Codespace
3. ไม่ Delete Codespace เพียงเพื่อหยุดค่าใช้จ่าย
4. ไม่ปล่อย Development Server รันค้างโดยไม่จำเป็น
5. ก่อน Stop ต้องตรวจ git status
6. งานที่เสร็จแล้วต้อง Commit และ Push
7. หลีกเลี่ยงบริการ Cloud / AI ที่มีค่าใช้จ่ายรายเดือนโดยไม่จำเป็น
8. AI แบบมีค่าใช้จ่ายต้องเป็น Optional และไม่เป็น Dependency หลักของ NG9 V1

---

# Change History

| Date | Decision | Change |
|------|----------|--------|
| 2026-08-12 | Initial Register | Created NG9 Project Decision Register |
