FROM node:12.18.1
ENV NODE_ENV=production

WORKDIR /var/www/project

COPY ["./app/package.json", "./app/package-lock.json*", "./"]

RUN npm install --production

COPY . .

CMD [ "node", "server.js" ]