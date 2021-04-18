import pixelWidth from "string-pixel-width";

const autoFitColumns = (json, worksheet, header) => {
    const jsonKeys = header || Object.keys(json[0]);

    const objectMaxLength = [];
    jsonKeys.forEach((key) => {
        objectMaxLength.push(
            pixelWidth(key, {
                size: 5,
            })
        );
    });

    json.forEach((data, i) => {
        const value = json[i];
        jsonKeys.forEach((key, j) => {
            const l = value[jsonKeys[j]]
                ? pixelWidth(value[jsonKeys[j]], {
                      size: 2,
                  })
                : 0;
            objectMaxLength[j] =
                objectMaxLength[j] >= l ? objectMaxLength[j] : l;
        });
    });

    return objectMaxLength.map((w) => {
        return { width: w };
    });
};

export default {
    autoFitColumns,
};
