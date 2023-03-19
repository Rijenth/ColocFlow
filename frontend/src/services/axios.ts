import axios from "axios";

const instance = axios.create({
  baseURL: "http://localhost:8000",
  withCredentials: true,
});

instance.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error) => {
    if (error.response !== undefined && error.response.status === 419) {
      await instance.get("/sanctum/csrf-cookie");
      error.config.headers["X-XSRF-TOKEN"] = document.cookie
        .split("; ")
        .find((row) => row.startsWith("XSRF-TOKEN="))
        ?.split("=")[1];

      return instance(error.config);
    }
    return Promise.reject(error);
  }
);

export default instance;
