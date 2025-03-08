import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  ignoreDeadLinks: true,
  base: '/livewire-simple-tables/',
  title: "Livewire Simple Tables",
  description: "A powerful Laravel Livewire package for creating beautiful data tables",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Installation', link: '/installation' },
      { text: 'Configuration', link: '/configuration' },
      { text: 'Usage', link: '/usage/basic' },
    ],

    sidebar: [
      {
        text: 'Getting Started',
        items: [
          { text: 'Introduction', link: '/' },
          { text: 'Installation', link: '/installation' },
          { text: 'Configuration', link: '/configuration' },
        ]
      },
      {
        text: 'Usage',
        items: [
          { text: 'Basic Usage', link: '/usage/basic' },
          { text: 'Advanced Usage', link: '/usage/advanced' },
          { text: 'Columns', link: '/usage/columns' },
          { text: 'Pagination', link: '/usage/pagination' },
          { text: 'Searching', link: '/usage/searching' },
          { text: 'Sorting', link: '/usage/sorting' },
          { text: 'Actions', link: '/usage/actions' },
          { text: 'Mutations', link: '/usage/mutations' },
          { text: 'Row Details', link: '/usage/row-details' },
          { text: 'Theming', link: '/usage/theming' },
        ]
      },
      {
        text: 'Advanced Components',
        items: [
          { text: 'Actions', link: '/components/actions' },
          { text: 'Bulk Actions', link: '/components/bulk-actions' },
          { text: 'Filters', link: '/components/filters' },
          { text: 'Placeholders', link: '/components/placeholders' },
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/Tiagospem/livewire-simple-tables' }
    ]
  }
})
