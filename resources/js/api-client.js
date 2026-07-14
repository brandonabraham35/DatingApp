export class ApiError extends Error {
    constructor(message, { status, payload } = {}) {
        super(message);
        this.name = 'ApiError';
        this.status = status;
        this.payload = payload;
    }
}

export class ApiClient {
    constructor(tokenProvider = () => localStorage.getItem('auth_token')) {
        this.tokenProvider = tokenProvider;
    }

    headers({ json = false, headers = {} } = {}) {
        const token = this.tokenProvider();

        return {
            Accept: 'application/json',
            ...(json ? { 'Content-Type': 'application/json' } : {}),
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
            ...headers,
        };
    }

    async request(url, options = {}) {
        const response = await fetch(url, {
            credentials: 'same-origin',
            ...options,
            headers: this.headers({
                json: options.json === true,
                headers: options.headers,
            }),
        });

        const contentType = response.headers.get('content-type') ?? '';
        const payload = contentType.includes('application/json')
            ? await response.json()
            : null;

        if (!response.ok) {
            throw new ApiError(
                payload?.message ?? `Request failed with status ${response.status}.`,
                { status: response.status, payload },
            );
        }

        return payload;
    }

    get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    }

    postJson(url, body, options = {}) {
        return this.request(url, {
            ...options,
            method: 'POST',
            json: true,
            body: JSON.stringify(body),
        });
    }

    postForm(url, formData, options = {}) {
        return this.request(url, {
            ...options,
            method: 'POST',
            body: formData,
        });
    }
}
