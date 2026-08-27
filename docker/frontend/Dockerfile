FROM node:24.19.0-alpine

ARG UID=1000
ARG GID=1000

RUN sed -i "s/^node:x:1000:1000:/node:x:$UID:$GID:/" /etc/passwd &&\
    sed -i "s/^node:x:1000:/node:x:$GID:/" /etc/group

RUN corepack enable pnpm
RUN corepack prepare pnpm@11.20.0 --activate
RUN mkdir -p /pnpm/store && chmod 777 /pnpm/store

USER node
    
ARG WORKDIR=/app
WORKDIR ${WORKDIR}

RUN corepack prepare pnpm@11.20.0 --activate

ARG PORT
ENV PORT=${PORT}
EXPOSE $PORT

ENV HOST=0.0.0.0

CMD [ "pnpm", "dev" ]
