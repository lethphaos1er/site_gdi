const bakeryCategories = [
    {
        id: 'pains',
        slug: 'pains',
        name: 'Pains',
        subcategories: [
            {
                id: 'baguettes',
                slug: 'baguettes',
                name: 'Baguettes',
                products: [],
            },
            {
                id: 'pains-speciaux',
                slug: 'pains-speciaux',
                name: 'Pains spéciaux',
                products: [],
            },
        ],
    },
    {
        id: 'viennoiseries',
        slug: 'viennoiseries',
        name: 'Viennoiseries',
        subcategories: [
{
    id: 'croissants',
    slug: 'croissants',
    name: 'Croissants',
    products: [
        {
            id: 'croissant-beurre',
            name: 'Croissant pur beurre',
            price: 1.25,
            stock: 20,
            image: null,
            description: 'Croissant feuilleté au beurre.',
        },
    ],
},
            {
                id: 'pains-au-chocolat',
                slug: 'pains-au-chocolat',
                name: 'Pains au chocolat',
                products: [],
            },
        ],
    },
    {
        id: 'patisseries',
        slug: 'patisseries',
        name: 'Pâtisseries',
        subcategories: [
            {
                id: 'tartes',
                slug: 'tartes',
                name: 'Tartes',
                products: [],
            },
            {
                id: 'gateaux',
                slug: 'gateaux',
                name: 'Gâteaux',
                products: [],
            },
        ],
    },
];

const italianCategories = [
    {
        id: 'pates',
        slug: 'pates',
        name: 'Pâtes',
        subcategories: [
            {
                id: 'pates-fraiches',
                slug: 'pates-fraiches',
                name: 'Pâtes fraîches',
                products: [],
            },
            {
                id: 'pates-seches',
                slug: 'pates-seches',
                name: 'Pâtes sèches',
                products: [],
            },
        ],
    },
    {
        id: 'epicerie-italienne',
        slug: 'epicerie-italienne',
        name: 'Épicerie italienne',
        subcategories: [
            {
                id: 'sauces',
                slug: 'sauces',
                name: 'Sauces',
                products: [],
            },
            {
                id: 'huiles',
                slug: 'huiles',
                name: 'Huiles',
                products: [],
            },
        ],
    },
];

const sportCategories = [
    {
        id: 'chaussures',
        slug: 'chaussures',
        name: 'Chaussures',
        subcategories: [
            {
                id: 'running',
                slug: 'running',
                name: 'Running',
                products: [],
            },
            {
                id: 'football',
                slug: 'football',
                name: 'Football',
                products: [],
            },
        ],
    },
    {
        id: 'vetements',
        slug: 'vetements',
        name: 'Vêtements',
        subcategories: [
            {
                id: 't-shirts',
                slug: 't-shirts',
                name: 'T-shirts',
                products: [],
            },
            {
                id: 'vestes',
                slug: 'vestes',
                name: 'Vestes',
                products: [],
            },
        ],
    },
];

export default [
    {
        id: 1,
        slug: 'boulangerie-ciney',
        name: 'Boulangerie Ciney',
        city: 'Ciney',
        type: 'bakery',
        apiBaseUrl: null,
        categories: bakeryCategories,
    },
    {
        id: 2,
        slug: 'boulangerie-dinant',
        name: 'Boulangerie Dinant',
        city: 'Dinant',
        type: 'bakery',
        apiBaseUrl: null,
        categories: bakeryCategories,
    },
    {
        id: 3,
        slug: 'boulangerie-namur',
        name: 'Boulangerie Namur',
        city: 'Namur',
        type: 'bakery',
        apiBaseUrl: null,
        categories: bakeryCategories,
    },
    {
        id: 4,
        slug: 'boulangerie-jambes',
        name: 'Boulangerie Jambes',
        city: 'Jambes',
        type: 'bakery',
        apiBaseUrl: null,
        categories: bakeryCategories,
    },
    {
        id: 5,
        slug: 'boulangerie-biron',
        name: 'Boulangerie Biron',
        city: 'Biron',
        type: 'bakery',
        apiBaseUrl: null,
        categories: bakeryCategories,
    },
    {
        id: 6,
        slug: 'italien',
        name: 'Italien',
        city: null,
        type: 'italian',
        apiBaseUrl: null,
        categories: italianCategories,
    },
    {
        id: 7,
        slug: 'sport',
        name: 'Sport',
        city: null,
        type: 'sport',
        apiBaseUrl: null,
        categories: sportCategories,
    },
];