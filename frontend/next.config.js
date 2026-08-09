/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    unoptimized: true,
    domains: ['127.0.0.1', 'localhost', 'qachouston.com', 'images.unsplash.com'],
  },
  experimental: {
    workerThreads: false,
    cpus: 1,
  },
}

module.exports = nextConfig
