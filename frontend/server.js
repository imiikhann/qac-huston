const { createServer } = require('http')
const next = require('next')

const dev = process.env.NODE_ENV !== 'production'
const app = next({ dev })
const handle = app.getRequestHandler()
const port = Number(process.env.PORT) || 3000
const primaryHost = 'qachouston.com'

const requestHandler = (req, res) => {
  const host = (req.headers.host || '').toLowerCase()
  if (host && host.includes(`app.${primaryHost}`)) {
    res.writeHead(301, { Location: `https://${primaryHost}${req.url}` })
    res.end()
    return
  }
  handle(req, res)
}

app.prepare().then(() => {
  const server = createServer(requestHandler)
  server.listen(port, '127.0.0.1', () => {
    console.log(`> Ready on http://127.0.0.1:${port}`)
  })
  server.on('error', (err) => {
    console.error(`> Listener error on 127.0.0.1:${port}: ${err.message}`)
  })
})
