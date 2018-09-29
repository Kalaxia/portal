const formDataToJson = formData => JSON.stringify(formDataToObject(formData));

const formDataToObject = formData => {
    let jsonObject = {};

    for (const [key, value]  of formData.entries()) {
        jsonObject[key] = value;
    }
    return jsonObject;
};