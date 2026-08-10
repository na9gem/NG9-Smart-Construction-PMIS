import { useState } from "react";
import api from "../api/api";

function Login({ onLogin }) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const login = async () => {
    setError("");
    setLoading(true);

    try {
      const response = await api.post("/login", {
        email,
        password,
      });

      const token = response.data.token;

      localStorage.setItem("ng9_token", token);
      localStorage.setItem(
        "ng9_user",
        JSON.stringify(response.data.user)
      );

      onLogin(response.data.user);
    } catch (error) {
      setError(
        error.response?.data?.message ||
        "ไม่สามารถเข้าสู่ระบบได้"
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1>NG9 Smart Construction PMIS</h1>

      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
      />

      <br />
      <br />

      <input
        type="password"
        placeholder="Password"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
      />

      <br />
      <br />

      <button onClick={login} disabled={loading}>
        {loading ? "กำลังเข้าสู่ระบบ..." : "Login"}
      </button>

      {error && (
        <p style={{ color: "red" }}>
          {error}
        </p>
      )}
    </div>
  );
}

export default Login;