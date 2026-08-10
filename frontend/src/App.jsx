import { useState } from "react";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";

function App() {
  const [user, setUser] = useState(() => {
    const savedUser = localStorage.getItem("ng9_user");

    return savedUser ? JSON.parse(savedUser) : null;
  });

  const logout = () => {
    localStorage.removeItem("ng9_token");
    localStorage.removeItem("ng9_user");

    setUser(null);
  };

  const handleLogin = (loggedInUser) => {
    setUser(loggedInUser);
  };

  if (!user) {
    return <Login onLogin={handleLogin} />;
  }

  return (
    <div>
      <div className="topbar">
        <span>ผู้ใช้งาน: {user.name}</span>

        <button onClick={logout}>
          Logout
        </button>
      </div>

      <Dashboard />
    </div>
  );
}

export default App;
