export const commonHeaderTemplate = (title) => {
    return {
        margin: [40, 50, 40, 0],
        stack: [
            {
                text: title,
                fontSize: 14,
                bold: true,
                alignment: "center",
            },
            {
                text: "Badan Pendapatan Daerah Kabupaten Bengkalis",
                fontSize: 12,
                margin: [0, 4, 0, 0],
                alignment: "center",
            },
            // horizontal line
            {
                alignment: "center",
                canvas: [
                    {
                        type: "line",
                        x1: 0,
                        y1: 10,
                        x2: 400,
                        y2: 10,
                        lineWidth: 1,
                    },
                ],
            },
        ],
    };
};
