// TODO: refactor to locale store/state
export const { format: formatDate } = Intl.DateTimeFormat('uk-UA', {
  year: 'numeric',
  month: 'long',
  day: 'numeric',
})

// TODO: use default currency from store -> config middleware
export const formatCurrency = (amount: string | number, currencyCode: string = "UAH") => {
  return new Intl.NumberFormat('uk-UA', {
    style: 'currency',
    currency: currencyCode,
    currencyDisplay: 'narrowSymbol',
  }).format(parseFloat(`${amount}`))
}
