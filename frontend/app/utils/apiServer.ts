export const getApiUrl = (): string => {
  const config = useRuntimeConfig()
  return import.meta.server ? config.apiServerUrl : config.public.apiServerUrl
}
