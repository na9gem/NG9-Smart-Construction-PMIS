import { BrowserRouter, Navigate, Route, Routes } from "react-router-dom";
import Login from "./pages/Login";
import Projects from "./pages/Projects";
import ProjectForm from "./pages/ProjectForm";
import Dashboard from "./pages/Dashboard";

function App() {
  const user = (() => {
    const savedUser = localStorage.getItem("ng9_user");

    return savedUser ? JSON.parse(savedUser) : null;
  })();

  if (!user) {
    return <Login onLogin={() => window.location.href = "/projects"} />;
  }

  return (
    <BrowserRouter>
      <div>
        <div className="topbar">
          <span>ผู้ใช้งาน: {user.name}</span>

          <button
            onClick={() => {
              localStorage.removeItem("ng9_token");
              localStorage.removeItem("ng9_user");
              window.location.href = "/login";
            }}
          >
            Logout
          </button>
        </div>

        <Routes>
          <Route
            path="/"
            element={<Navigate to="/projects" replace />}
          />

          <Route
            path="/login"
            element={<Login onLogin={() => window.location.href = "/projects"} />}
          />

          <Route
            path="/projects"
            element={<Projects />}
          />

         <Route
            path="/projects/new"
            element={<ProjectForm />}
          />
           <Route
            path="/projects/:projectId/dashboard"
            element={<Dashboard />}
          />

          <Route
            path="*"
            element={<Navigate to="/projects" replace />}
          />
        </Routes>
      </div>
    </BrowserRouter>
  );
}

export default App;

