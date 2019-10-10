Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'gitlog',
            path: '/gitlog',
            component: require('./components/Tool'),
        },
    ])
})
