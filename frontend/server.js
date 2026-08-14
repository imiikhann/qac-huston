const { createServer } = require('http')
const next = require('next')

const dev = process.env.NODE_ENV !== 'production'
const app = next({ dev })
const handle = app.getRequestHandler()
const port = process.env.PORT || 3000
const internalPort = 3000
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
  createServer(requestHandler).listen(port, () => {
    console.log(`> Ready on http://localhost:${port}`)
  })

  if (String(port) !== String(internalPort)) {
    try {
      const internal = createServer(requestHandler)
      internal.listen(internalPort, '127.0.0.1', () => {
        console.log(`> Internal listener on 127.0.0.1:${internalPort}`)
      })
      internal.on('error', (err) => {
        console.error(`> Internal listener failed on 127.0.0.1:${internalPort}: ${err.message}`)
      })
    } catch (err) {
      console.error(`> Could not start internal listener on 127.0.0.1:${internalPort}: ${err.message}`)
    }
  }
})
