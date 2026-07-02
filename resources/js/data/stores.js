export default [
    {
        id: 1,
        name: "GDI Liège",
        city: "Liège",
        logo: "/images/stores/liege.webp",
        departments: [
            {
                id: 1,
                name: "Fruits et légumes",
                products: [
                    {
                        id: 1,
                        name: "Pommes Jonagold",
                        description: "Pommes fraîches disponibles en magasin.",
                        price: 2.49,
                        stock: 24,
                        image: "/images/products/pommes.webp",
                    },
                    {
                        id: 2,
                        name: "Carottes",
                        description: "Carottes croquantes pour vos repas du quotidien.",
                        price: 1.89,
                        stock: 36,
                        image: "/images/products/carottes.webp",
                    },
                ],
            },
            {
                id: 2,
                name: "Boucherie",
                products: [
                    {
                        id: 3,
                        name: "Filet de poulet",
                        description: "Filet de poulet tendre et facile à cuisiner.",
                        price: 9.9,
                        stock: 12,
                        image: "/images/products/filet-poulet.webp",
                    },
                ],
            },
            {
                id: 3,
                name: "Boulangerie",
                products: [
                    {
                        id: 4,
                        name: "Baguette tradition",
                        description: "Baguette croustillante cuite du jour.",
                        price: 1.2,
                        stock: 18,
                        image: "/images/products/baguette.webp",
                    },
                ],
            },
        ],
    },
    {
        id: 2,
        name: "GDI Namur",
        city: "Namur",
        logo: "/images/stores/namur.webp",
        departments: [
            {
                id: 1,
                name: "Fruits et légumes",
                products: [
                    {
                        id: 5,
                        name: "Bananes",
                        description: "Bananes mûres à point.",
                        price: 1.99,
                        stock: 30,
                        image: "/images/products/bananes.webp",
                    },
                ],
            },
            {
                id: 2,
                name: "Boucherie",
                products: [
                    {
                        id: 6,
                        name: "Haché porc et bœuf",
                        description: "Haché idéal pour sauces, boulettes et préparations maison.",
                        price: 8.9,
                        stock: 10,
                        image: "/images/products/hache.webp",
                    },
                ],
            },
            {
                id: 3,
                name: "Boulangerie",
                products: [],
            },
        ],
    },
    {
        id: 3,
        name: "GDI Verviers",
        city: "Verviers",
        logo: "/images/stores/verviers.webp",
        departments: [],
    },
];