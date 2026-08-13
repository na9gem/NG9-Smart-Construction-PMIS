import { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";

function ProjectForm() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    project_code: "",
    project_name: "",
    owner: "",
    contractor: "",
    consultant: "",
    location: "",
    budget: "",
    contract_number: "",
    contract_amount: "",
    progress_percent: "0",
    planned_start_date: "",
    planned_finish_date: "",
    actual_finish_date: "",
    status: "Draft",
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleChange = (event) => {
    const { name, value } = event.target;

    setForm((current) => ({
      ...current,
      [name]: value,
    }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      setLoading(true);
      setError("");

      await api.post("/projects", {
        ...form,
        budget: Number(form.budget),
        contract_amount: form.contract_amount
          ? Number(form.contract_amount)
          : null,
        progress_percent: Number(form.progress_percent),
      });

      navigate("/projects");
    } catch (err) {
      console.error(err);

      if (err.response?.data?.errors) {
        const messages = Object.values(
          err.response.data.errors
        ).flat();

        setError(messages.join(" "));
      } else {
        setError(
          err.response?.data?.message ||
            "ไม่สามารถบันทึกโครงการได้"
        );
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="project-form-page">
      <header>
        <h1>สร้างโครงการ</h1>
        <p>กรอกข้อมูลพื้นฐานของโครงการ</p>
      </header>

      {error && (
        <div
          style={{
            color: "red",
            marginBottom: "16px",
          }}
        >
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit}>
        <section className="dashboard-card">
          <h2>ข้อมูลโครงการ</h2>

          <div className="info-grid">
            <div>
              <label htmlFor="project_code">
                รหัสโครงการ *
              </label>

              <input
                id="project_code"
                name="project_code"
                value={form.project_code}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="project_name">
                ชื่อโครงการ *
              </label>

              <input
                id="project_name"
                name="project_name"
                value={form.project_name}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="owner">
                หน่วยงานเจ้าของ *
              </label>

              <input
                id="owner"
                name="owner"
                value={form.owner}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="location">
                สถานที่
              </label>

              <input
                id="location"
                name="location"
                value={form.location}
                onChange={handleChange}
              />
            </div>
          </div>
        </section>

        <section className="dashboard-card">
          <h2>ผู้เกี่ยวข้อง</h2>

          <div className="info-grid">
            <div>
              <label htmlFor="contractor">
                ผู้รับจ้าง
              </label>

              <input
                id="contractor"
                name="contractor"
                value={form.contractor}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="consultant">
                ที่ปรึกษา
              </label>

              <input
                id="consultant"
                name="consultant"
                value={form.consultant}
                onChange={handleChange}
              />
            </div>
          </div>
        </section>

        <section className="dashboard-card">
          <h2>งบประมาณและสัญญา</h2>

          <div className="info-grid">
            <div>
              <label htmlFor="budget">
                งบประมาณ *
              </label>

              <input
                id="budget"
                name="budget"
                type="number"
                min="0"
                step="0.01"
                value={form.budget}
                onChange={handleChange}
                required
              />
            </div>

            <div>
              <label htmlFor="contract_number">
                เลขที่สัญญา
              </label>

              <input
                id="contract_number"
                name="contract_number"
                value={form.contract_number}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="contract_amount">
                วงเงินตามสัญญา
              </label>

              <input
                id="contract_amount"
                name="contract_amount"
                type="number"
                min="0"
                step="0.01"
                value={form.contract_amount}
                onChange={handleChange}
              />
            </div>
          </div>
        </section>

        <section className="dashboard-card">
          <h2>ระยะเวลาและสถานะโครงการ</h2>

          <div className="info-grid">
            <div>
              <label htmlFor="planned_start_date">
                วันเริ่มโครงการ
              </label>

              <input
                id="planned_start_date"
                name="planned_start_date"
                type="date"
                value={form.planned_start_date}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="planned_finish_date">
                วันสิ้นสุดโครงการ
              </label>

              <input
                id="planned_finish_date"
                name="planned_finish_date"
                type="date"
                value={form.planned_finish_date}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="actual_finish_date">
                วันแล้วเสร็จจริง
              </label>

              <input
                id="actual_finish_date"
                name="actual_finish_date"
                type="date"
                value={form.actual_finish_date}
                onChange={handleChange}
              />
            </div>

            <div>
              <label htmlFor="status">
                สถานะ *
              </label>

              <select
                id="status"
                name="status"
                value={form.status}
                onChange={handleChange}
                required
              >
                <option value="Draft">Draft</option>
                <option value="Tender">Tender</option>
                <option value="Construction">
                  Construction
                </option>
                <option value="Completed">
                  Completed
                </option>
                <option value="OnHold">OnHold</option>
                <option value="Cancelled">
                  Cancelled
                </option>
              </select>
            </div>

            <div>
              <label htmlFor="progress_percent">
                ความก้าวหน้า (%)
              </label>

              <input
                id="progress_percent"
                name="progress_percent"
                type="number"
                min="0"
                max="100"
                step="0.01"
                value={form.progress_percent}
                onChange={handleChange}
              />
            </div>
          </div>
        </section>

        <div>
          <button type="submit" disabled={loading}>
            {loading
              ? "กำลังบันทึก..."
              : "บันทึกโครงการ"}
          </button>
        </div>
      </form>
    </div>
  );
}

export default ProjectForm;