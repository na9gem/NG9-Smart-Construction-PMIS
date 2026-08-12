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

**Status:** PROPOSED

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
