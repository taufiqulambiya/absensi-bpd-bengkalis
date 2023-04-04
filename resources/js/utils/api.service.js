import $ from "jquery";

export const getJson = (url) => {
    return new Promise((resolve) => {
        return $.getJSON(`${url}?mode=json`, (data) => {
            resolve(data);
        });
    });
};
