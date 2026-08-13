import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import api from "../api/api";
import SCurveChart from "../components/SCurveChart";
import ProgressReports from "../components/ProgressReports";

function Dashboard() {
  const { projectId } = useParams();

  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

const loadDashboard = async () => {
    if (!projectId) {
      setError("ไม่พบรหัสโครงการ");
      setLoading(false);
      return;
    }

    try {
      setLoading(true);
      setError("");

      const response = await api.get(
        `/dashboard/projects/${projectId}/s-curve`
      );

      setData(response.data.data);
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "ไม่สามารถโหลดข้อมูล Dashboard ได้"
      );
    } finally {
      setLoading(false);
    }
  };

useEffect(() => {
  loadDashboard();
}, [projectId]);
  if (loading) {
    return <p>กำลังโหลด Dashboard...</p>;
  }

  if (error) {
    return (
      <div>
        <h2>Dashboard</h2>
        <p style={{ color: "red" }}>{error}</p>
      </div>
    );
  }

  if (!data) {
    return <p>ไม่พบข้อมูล Dashboard</p>;
  }

  const project = data.project;
  const contract = data.contract;
  const progress = data.progress;

  return (
    <div className="dashboard">

      <header className="dashboard-header">
        <h1>NG9 Smart Construction PMIS</h1>

        <p>
          {project.project_code} — {project.project_name}
        </p>
      </header>

      <section className="summary-grid">

        <div className="summary-card">
          <span>Planned</span>
          <strong>{progress.planned}%</strong>
        </div>

        <div className="summary-card">
          <span>Actual</span>
          <strong>{progress.actual}%</strong>
        </div>

        <div className="summary-card">
          <span>Variance</span>
          <strong>{progress.variance}%</strong>
        </div>

      </section>

      <section className="dashboard-card">

        <h2>ข้อมูลโครงการ</h2>

        <div className="info-grid">

          <div>
            <span>สถานะ</span>
            <strong>{project.status}</strong>
          </div>

          <div>
            <span>สถานที่</span>
            <strong>{project.location || "-"}</strong>
          </div>

          <div>
            <span>ผู้รับจ้าง</span>
            <strong>{project.contractor || "-"}</strong>
          </div>

          <div>
            <span>ที่ปรึกษา</span>
            <strong>{project.consultant || "-"}</strong>
          </div>

        </div>

      </section>

      <section className="dashboard-card">

        <h2>ข้อมูลสัญญา</h2>

        <div className="info-grid">

          <div>
            <span>เลขที่สัญญา</span>
            <strong>
              {contract?.contract_no || "-"}
            </strong>
          </div>

          <div>
            <span>วงเงินสัญญา</span>
            <strong>
              {contract
                ? Number(
                    contract.contract_amount
                  ).toLocaleString("th-TH") + " บาท"
                : "-"}
            </strong>
          </div>

          <div>
            <span>วันเริ่มสัญญา</span>
            <strong>
              {contract?.start_date
                ? contract.start_date.substring(0, 10)
                : "-"}
            </strong>
          </div>

          <div>
            <span>วันสิ้นสุดสัญญา</span>
            <strong>
              {contract?.finish_date
                ? contract.finish_date.substring(0, 10)
                : "-"}
            </strong>
          </div>

        </div>

      </section>

      <section className="dashboard-card">

        <h2>S-Curve</h2>

<SCurveChart
  data={progress.s_curve}
  actualData={progress.actual_s_curve}
/>


      </section>

      <section className="dashboard-card">

        <h2>งวดงาน</h2>

        <div className="report-table-wrapper">

          <table className="milestone-table">

            <thead>
              <tr>
                <th>งวด</th>
                <th>รายการ</th>
                <th>กำหนดแล้วเสร็จ</th>
                <th>สัดส่วน</th>
                <th>จำนวนเงิน</th>
                <th>สถานะ</th>
              </tr>
            </thead>

            <tbody>

              {data.milestones.items.map(
                (milestone) => (
                  <tr key={milestone.id}>

                    <td>
                      {milestone.milestone_no}
                    </td>

                    <td>
                      {milestone.milestone_name}
                    </td>

                    <td>
                      {milestone.planned_finish_date
                        ? milestone.planned_finish_date.substring(
                            0,
                            10
                          )
                        : "-"}
                    </td>

                    <td>
                      {milestone.payment_percent}%
                    </td>

                    <td>
                      {Number(
                        milestone.payment_amount
                      ).toLocaleString("th-TH")}{" "}
                      บาท
                    </td>

                    <td>
                      <span className="status-badge">
                        {milestone.status}
                      </span>
                    </td>

                  </tr>
                )
              )}

            </tbody>

          </table>

        </div>

      </section>

      <ProgressReports
        projectId={project.id}
        onSaved={loadDashboard}
      />

    </div>
  );
}

export default Dashboard;
