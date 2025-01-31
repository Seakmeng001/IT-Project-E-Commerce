import axios from "axios";

// Function to get a specific cookie value
function getCookie(name) {
  const match = document.cookie.match(new RegExp(`(^| )${name}=([^;]+)`));
  if (match) return match[2];
  return null;
}

async function fetchData(method, url, data) {
  try {
    const token = getCookie("token");

    const headers = {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
      Accept: "application/json",
    };

    const response = await axios({
      method: method,
      url: url,
      data: data,
      headers: headers,
    });

    return response.data;
  } catch (error) {
    if (error.response && error.response.status === 401) {
      window.location.href = "/login";
      return;
    }
    console.error("Error fetching data:", error);
    throw error;
  }
}

// Add an interceptor to handle unauthorized responses globally
// axios.interceptors.response.use(
//   (response) => response,
//   (error) => {
//     if (error.response && error.response.status === 401) {
//       // Redirect to login page
//       window.location.href = "/auth/login";
//     }
//     return Promise.reject(error);
//   }
// );

export default fetchData;
