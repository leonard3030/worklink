export default [{
    _name: 'CSidebarNav',
    _children: [
        {
            _name: 'CSidebarNavItem',
            name: 'Dashboard',
            to: '/admin/report',
            icon: 'cil-speedometer',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Published Scripts',
            to: '/admin/publishedScripts',
            icon: 'cil-task',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Pending Scripts',
            to: '/admin/pendingScripts',
            icon: 'cil-task',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Account Requests',
            to: '/admin/writters/requests',
            icon: 'cil-bell',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Approved Writters',
            to: '/admin/writters',
            icon: 'cil-people',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Orders',
            to: '/admin/orders',
            icon: 'cil-dollar',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Clients',
            to: '/admin/clients',
            icon: 'cil-people',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Contacts',
            to: '/admin/contacts',
            icon: 'cil-envelope-closed',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Consultancy',
            to: '/admin/consultancy',
            icon: 'cil-pencil',
        },
        {
            _name: 'CSidebarNavItem',
            name: 'Users',
            to: '/admin/users',
            icon: 'cil-settings',
        },

    ]
}]