const { createServer } = require('http')
const next = require('next')

const dev = process.env.NODE_ENV !== 'production'
const app = next({ dev })
const handle = app.getRequestHandler()
const port = process.env.PORT || 3000
const internalPort = 3000

app.prepare().then(() => {
  createServer((req, res) => handle(req, res)).listen(port, () => {
    console.log(`> Ready on http://localhost:${port}`)
  })

  if (String(port) !== String(internalPort)) {
    createServer((req, res) => handle(req, res)).listen(internalPort, '127.0.0.1', () => {
      console.log(`> Internal listener on 127.0.0.1:${internalPort}`)
    })
  }
})
