const BASE = import.meta.env.VITE_API_URL || "http://localhost:8000/api";
export const STORAGE_BASE = BASE.replace(/\/api$/, "");

export const getToken = () => localStorage.getItem("ibhy_token");

export async function api(path, { method = "GET", body, auth = false } = {}) {
    const headers = { Accept: "application/json" };
    if (auth) headers.Authorization = `Bearer ${getToken()}`;

    const isForm = body instanceof FormData;
    if (body && !isForm) headers["Content-Type"] = "application/json";

    const res = await fetch(`${BASE}${path}`, {
        method,
        headers,
        body: body ? (isForm ? body : JSON.stringify(body)) : undefined,
    });

    if (!res.ok) throw await res.json().catch(() => ({ message: res.statusText }));
    return res.status === 204 ? null : res.json();
}

export const formatRupiah = (n) => "IDR" + Number(n || 0).toLocaleString("id-ID");