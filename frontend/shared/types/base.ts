export interface ResponseMeta {
  total: number
  currentPage: number
  limit: number
  offset: number
}

export interface ListResponse<T> {
  data: T[]
}

export interface PageableResponse<T> extends ListResponse<T> {
  meta: ResponseMeta
}
