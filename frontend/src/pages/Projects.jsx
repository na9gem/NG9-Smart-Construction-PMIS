import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/api";

function Projects() {
  const navigate = useNavigate();

  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const loadProjects = async () => {
    try {
      setLoading(true);
      setError("");

      const response = await api.get("/projects");

      setProjects(response.data.data || []);
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "ไม่สามารถโหลดรายการโครงการได้"
      );
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProjects();
  }, []);

  if (loading) {
    return <p>กำลังโหลดรายการโครงการ...</p>;
  }

  if (error) {
    return (
      <div>
        <h1>โครงการ</h1>
        <p style={{ color: "red" }}>{error}</p>

        <button onClick={loadProjects}>
          ลองใหม่
        </button>
      </div>
    );
  }

  return (
    <div className="projects-page">
      <header>
        <h1>โครงการ</h1>
        <p>เลือกโครงการเพื่อเข้าสู่ Project Dashboard</p>
      </header>

      {projects.length === 0 ? (
        <p>ยังไม่มีข้อมูลโครงการ</p>
      ) : (
        <div className="projects-list">
          {projects.map((project) => (
            <div
              key={project.id}
              className="project-card"
            >
              <h2>{project.project_name}</h2>

              <p>
                รหัสโครงการ: {project.project_code}
              </p>

              <p>
                หน่วยงาน: {project.owner}
              </p>

              <p>
                ผู้รับจ้าง: {project.contractor || "-"}
              </p>

              <p>
                สถานะ: {project.status}
              </p>

              <button
                onClick={() =>
                  navigate(
                    `/projects/${project.id}/dashboard`
                  )
                }
              >
                เปิดโครงการ
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default Projects;
