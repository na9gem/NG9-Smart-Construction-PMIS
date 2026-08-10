import { useEffect, useState } from "react";
import api from "../api/api";

function ProgressReports({ projectId, onSaved }) {
  const [reports, setReports] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");

  const [form, setForm] = useState({
    report_date: new Date().toISOString().substring(0, 10),
    progress_percent: "",
    work_description: "",
    problem: "",
    solution: "",
    weather: "",
    manpower: "",
    status: "Draft",
  });

  const loadReports = async () => {
    try {
      setLoading(true);
      setError("");

      const response = await api.get("/progress-reports");

      const items = response.data.data || [];

      setReports(
        items
          .filter(
            (item) =>
              Number(item.project_id) === Number(projectId)
          )
          .sort(
            (a, b) =>
              new Date(b.report_date) -
              new Date(a.report_date)
          )
      );
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "ไม่สามารถโหลด Progress Report ได้"
      );
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadReports();
  }, [projectId]);

  const handleChange = (event) => {
    const { name, value } = event.target;

    setForm((current) => ({
      ...current,
      [name]: value,
    }));
  };

  const submit = async (event) => {
    event.preventDefault();

    try {
      setSaving(true);
      setError("");

      await api.post("/progress-reports", {
        project_id: Number(projectId),

        // Demo Contract ของ Project 3
        contract_id: 2,

        report_date: form.report_date,

        progress_percent: Number(
          form.progress_percent
        ),

        work_description:
          form.work_description,

        problem: form.problem || null,

        solution: form.solution || null,

        weather: form.weather || null,

        manpower: form.manpower
          ? Number(form.manpower)
          : 0,

        status: form.status,
      });

      setForm({
        report_date: new Date()
          .toISOString()
          .substring(0, 10),

        progress_percent: "",
        work_description: "",
        problem: "",
        solution: "",
        weather: "",
        manpower: "",
        status: "Draft",
      });

      await loadReports();

      if (onSaved) {
        await onSaved();
      }
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "ไม่สามารถบันทึก Progress Report ได้"
      );
    } finally {
      setSaving(false);
    }
  };

  return (
    <section className="dashboard-card progress-report-section">

      <div className="section-header">
        <div>
          <h2>Progress Report</h2>

          <p className="section-description">
            รายงานความก้าวหน้างานก่อสร้าง
          </p>
        </div>
      </div>

      {error && (
        <div className="error-message">
          {error}
        </div>
      )}

      <form
        className="progress-report-form"
        onSubmit={submit}
      >

        <div className="form-grid">

          <label>
            วันที่รายงาน

            <input
              type="date"
              name="report_date"
              value={form.report_date}
              onChange={handleChange}
              required
            />
          </label>

          <label>
            ความก้าวหน้า (%)

            <input
              type="number"
              name="progress_percent"
              min="0"
              max="100"
              step="0.01"
              value={form.progress_percent}
              onChange={handleChange}
              placeholder="เช่น 5"
              required
            />
          </label>

          <label>
            จำนวนแรงงาน

            <input
              type="number"
              name="manpower"
              min="0"
              value={form.manpower}
              onChange={handleChange}
              placeholder="เช่น 20"
            />
          </label>

          <label>
            สภาพอากาศ

            <input
              type="text"
              name="weather"
              value={form.weather}
              onChange={handleChange}
              placeholder="เช่น ปกติ"
            />
          </label>

        </div>

        <label>
          รายละเอียดงาน

          <textarea
            name="work_description"
            value={form.work_description}
            onChange={handleChange}
            placeholder="ระบุรายละเอียดงานที่ดำเนินการ"
            rows="3"
            required
          />
        </label>

        <label>
          ปัญหา / อุปสรรค

          <textarea
            name="problem"
            value={form.problem}
            onChange={handleChange}
            placeholder="ถ้ามี"
            rows="2"
          />
        </label>

        <label>
          แนวทางแก้ไข

          <textarea
            name="solution"
            value={form.solution}
            onChange={handleChange}
            placeholder="ถ้ามี"
            rows="2"
          />
        </label>

        <label>
          สถานะ

          <select
            name="status"
            value={form.status}
            onChange={handleChange}
          >
            <option value="Draft">
              Draft
            </option>

            <option value="Submitted">
              Submitted
            </option>

            <option value="Approved">
              Approved
            </option>
          </select>
        </label>

        <div className="form-actions">

          <button
            type="submit"
            disabled={saving}
          >
            {saving
              ? "กำลังบันทึก..."
              : "บันทึก Progress Report"}
          </button>

        </div>

      </form>

      <div className="progress-report-list">

        <h3>รายงานที่ผ่านมา</h3>

        {loading ? (
          <p>กำลังโหลด...</p>
        ) : reports.length === 0 ? (
          <p>ยังไม่มี Progress Report</p>
        ) : (
          <div className="report-table-wrapper">

            <table className="progress-report-table">

              <thead>
                <tr>
                  <th>วันที่</th>
                  <th>ความก้าวหน้า</th>
                  <th>รายละเอียดงาน</th>
                  <th>แรงงาน</th>
                  <th>สถานะ</th>
                </tr>
              </thead>

              <tbody>

                {reports.map((report) => (
                  <tr key={report.id}>

                    <td>
                      {report.report_date
                        ? report.report_date.substring(
                            0,
                            10
                          )
                        : "-"}
                    </td>

                    <td>
                      {report.progress_percent}%
                    </td>

                    <td>
                      {report.work_description ||
                        "-"}
                    </td>

                    <td>
                      {report.manpower ?? 0}
                    </td>

                    <td>
                      <span className="status-badge">
                        {report.status}
                      </span>
                    </td>

                  </tr>
                ))}

              </tbody>

            </table>

          </div>
        )}

      </div>

    </section>
  );
}

export default ProgressReports;