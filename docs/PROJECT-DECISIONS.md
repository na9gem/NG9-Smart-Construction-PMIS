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

**Status:** PROPOSED

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
