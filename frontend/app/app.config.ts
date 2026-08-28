export default defineAppConfig({
  ui: {
    formField: {
      variants: {
        orientation: {
          horizontal: {
            wrapper: 'w-72',
            container: 'grow-1',
          }
        }
      }
    },
    input: {
      slots: {
        root: 'min-w-50 w-full md:w-80'
      }
    }
  }
})
