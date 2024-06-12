const clear_form = (data) => {
    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        data = {
            modal_id : 'packinglist_modal',
            title: "Add Product",
            btn_submit: "Add Product",
            form_action_url: "",
        };
        * --------------------------------------------------------------------
    */

    $(`#${data.modal_id} .modal-title`).text(data.title);
    $(`#${data.modal_id} .btn-submit`).text(data.btn_submit);
    $(`#${data.modal_id} form`).attr(`action`, data.form_action_url);
    $(`#${data.modal_id} form`).find("input[type=text], input[type=number], input[type=email], input[type=hidden], input[type=password], textarea").val("");
    $(`#${data.modal_id} form`).find(`select`).val("").trigger(`change`);
    $(`#${data.modal_id} form`).find('input,select').removeClass("is-invalid");
    $(`#${data.modal_id} form`).find('span.invalid-feedback').css('display', 'none');
}

const stopFormSubmission = (event) => {
    event.preventDefault();
}

const getFormData = (form) => {
    if (!form) return {};

    const formData = new FormData(form);
    const formDataObject = {};

    formData.forEach((value, key) => {
        formDataObject[key] = value;
    });

    return formDataObject;
}

const using_fetch = async ({ url = "", data = {}, method = "GET", token = null }) => {

    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        data = {
            url: "https://example.com/api",
            method: "POST",
            data: { key: "value" },
            token: "your_token",
        }
        * --------------------------------------------------------------------
    */

    const headers = {
        "Content-Type": "application/json",
    };

    const fetchOptions = {
        method,
        headers,
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        redirect: "follow",
        referrerPolicy: "no-referrer",
    };

    if (["GET"].includes(method)) {
        const queryString = new URLSearchParams(data).toString();
        url = `${url}?${queryString}`;
    }

    if (["POST", "PUT", "DELETE"].includes(method)) {
        headers["X-CSRF-TOKEN"] = token;
        fetchOptions.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, fetchOptions);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return response.json();
    } catch (error) {
        console.error("Fetch error:", error);
        throw error;
    }
}


const using_fetch_with_loader = async ({ url = "", data = {}, method = "GET", token = null, loader = false }) => {

    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        data = {
            url: "https://example.com/api",
            method: "POST",
            data: { key: "value" },
            token: "your_token",
        }
        * --------------------------------------------------------------------
    */

    const headers = {
        "Content-Type": "application/json",
    };

    const fetchOptions = {
        method,
        headers,
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        redirect: "follow",
        referrerPolicy: "no-referrer",
    };

    if (["GET"].includes(method)) {
        const queryString = new URLSearchParams(data).toString();
        url = `${url}?${queryString}`;
    }

    if (["POST", "PUT", "DELETE"].includes(method)) {
        headers["X-CSRF-TOKEN"] = token;
        fetchOptions.body = JSON.stringify(data);
    }

    try {
        if (loader) {

            // ## Show loading spinner
            Swal.fire({
                title: 'Loading..',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        const response = await fetch(url, fetchOptions);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        if (loader) {
            // Hide loading spinner
            Swal.close();
        }

        return response.json();
    } catch (error) {
        console.error("Fetch error:", error);
        throw error;
    }
}


const show_flash_message = (session = {}) => {
    if ("success" in session) {
        Swal.fire({
            icon: "success",
            title: session.success,
            showConfirmButton: false,
            timer: 4000,
        });
    }
    if ("error" in session) {
        Swal.fire({
            icon: "error",
            title: session.error,
            confirmButtonColor: "#007bff",
        });
    }
}

const swal_info = (data = { title: "Success", reload_option: false }) => {
    const afterClose = () => {
        if (data.reload_option == true) {
            location.reload();
        } else {
            return false;
        }
    }
    Swal.fire({
        icon: "success",
        title: data.title,
        showConfirmButton: false,
        timer: data.timer ? data.timer : 2000,
        didClose: afterClose,
    });
};

const swal_failed = (data) => {
    Swal.fire({
        icon: "error",
        title: data.title ? data.title : "Something Error",
        text: data.text ? data.text : 'Please contact the Administrator',
        showConfirmButton: true,
    });
}

const swal_warning = (data) => {
    Swal.fire({
        icon: "warning",
        title: data.title ? data.title : "Caution!",
        text: data.text ? data.text : null,
        showConfirmButton: true,
    });
}

const swal_confirm = (data = {}) => {

    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        const result = await swal_confirm({
            title: "Custom Title",
            text: "Custom Text",
            icon: "info",
            confirmButton: "Save"
            confirmButtonClass: "btn-primary",
            cancelButtonClass: "btn-secondary"
        });
        * --------------------------------------------------------------------
    */


    const swalComponent = Swal.mixin({
        customClass: {
            confirmButton: `btn ${data.confirmButtonClass || "btn-primary"} m-2`,
            cancelButton: `btn ${data.cancelButtonClass || "btn-secondary"} m-2`,
        },
        buttonsStyling: false,
    });

    const title = data.title || "Are you sure?";
    const confirmButton = data.confirmButton || "Save";
    const icon = data.icon || "question";

    return new Promise((resolve, reject) => {
        swalComponent
            .fire({
                title: title,
                text: data.text,
                confirmButtonText: confirmButton,
                icon: icon,
                showCancelButton: true,
                reverseButtons: true,
            })
            .then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            })
            .catch((error) => {
                reject(error);
            });
    });
}

const swal_confirm_loader = (data = {}) => {

    /*
        * --------------------------------------------------------------------
        * Params Example
        * --------------------------------------------------------------------
        const result = await swal_confirm({
            title: "Custom Title",
            text: "Custom Text",
            icon: "info",
            confirmButton: "Save"
            confirmButtonClass: "btn-primary",
            cancelButtonClass: "btn-secondary"
            loader: true,
            fetch_data: fetch_data,
        });
        * --------------------------------------------------------------------
    */


    const swalComponent = Swal.mixin({
        customClass: {
            confirmButton: `btn ${data.confirmButtonClass || "btn-primary"} m-2`,
            cancelButton: `btn ${data.cancelButtonClass || "btn-secondary"} m-2`,
        },
        buttonsStyling: false,
    });

    const title = data.title || "Are you sure?";
    const confirmButton = data.confirmButton || "Save";
    const icon = data.icon || "question";

    return new Promise((resolve, reject) => {
        swalComponent
            .fire({
                title: title,
                text: data.text,
                confirmButtonText: confirmButton,
                icon: icon,
                showCancelButton: true,
                reverseButtons: true,
                allowOutsideClick: false,
                preConfirm: () => {
                    if (data.loader) {
                        // Menonaktifkan tombol delete dan menampilkan loader
                        const deleteButton = document.querySelector('.swal2-confirm');
                        if (deleteButton) {
                            deleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                            setTimeout(() => {
                                deleteButton.disabled = true;
                            }, 10);
                        }
                    }

                    using_fetch(data.fetch_data).then((result) => {
                        if(result.status == "success"){
                            swal_info({
                                title : result.message,
                            });

                            reload_dtable();
                        } else {
                            swal_failed({ title: result.message });
                        }
                    })

                    return false;
                },
            })
            .then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            })
            .catch((error) => {
                reject(error);
            });
    });
}

const using_axios = async ({ url = "", data = {}, method = "GET", token = null }) => {

    /*
        * --------------------------------------------------------------------
        * Example Parameters
        * --------------------------------------------------------------------
        * url: "https://example.com/api"
        * method: "POST"
        * data: { key: "value" }
        * --------------------------------------------------------------------
    */
    const axiosOption = {
        method,
        headers: {
            Authorization: `Bearer ${token}`
        },
        validateStatus: (status) => status >= 200 && status <= 404,
    };

    if (method === "GET") {
        const queryString = new URLSearchParams(data).toString();
        url = `${url}?${queryString}`;
    }

    axiosOption.url = url;
    axiosOption.data = data;

    try {
        const response = await axios(url, axiosOption);
        if ([200, 404].includes(response.status)) {
            return response.data;
        }

        if (response.status === 401) {
            return {
                status: response.status,
                message: response.statusText,
            };
        }

        throw new Error(`Unknown! Status: ${response.status}`);
    } catch (error) {
        console.error("Axios error:", error.message);
        throw error;
    }
}

const load_component = async ({ url = "", data = {}, token = null, container_element_id }) => {
    let fetch_data = {
        url: url,
        method: "GET",
        data: data,
        token: token,
    }
    let result = await using_fetch(fetch_data);
    document.getElementById(container_element_id).innerHTML = result.component;
}


const fill_table_with_default_data = ({ table_selector, num_columns, default_data }) => {
    const table = document.querySelector(table_selector);
    const tbody = table.querySelector('tbody');
    const row = document.createElement('tr');
    row.classList.add('empty-row-table');
    const cell = document.createElement('td');
    cell.colSpan = num_columns;
    cell.textContent = default_data;
    row.appendChild(cell);
    tbody.innerHTML = '';
    tbody.appendChild(row);
}

window.stopFormSubmission = stopFormSubmission;
window.clear_form = clear_form;
window.getFormData = getFormData;
window.using_fetch = using_fetch;
window.swal_info = swal_info;
window.swal_confirm = swal_confirm;
window.swal_confirm_loader = swal_confirm_loader;
window.swal_failed = swal_failed;
window.show_flash_message = show_flash_message;
window.swal_warning = swal_warning;
window.load_component = load_component;
window.using_axios = using_axios;
window.fill_table_with_default_data = fill_table_with_default_data;