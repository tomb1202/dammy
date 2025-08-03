module.exports = {
  apps: [
    {
      name: 'queue-images',
      script: 'artisan',
      args: 'queue:work --queue=images --sleep=1 --timeout=300 --tries=3',
      interpreter: 'php',
      instances: 5,
      watch: false,
    },
    {
      name: 'queue-comics',
      script: 'artisan',
      args: 'queue:work --queue=comics --sleep=1 --timeout=300 --tries=3',
      interpreter: 'php',
      instances: 5,
      watch: false,
    },
    {
      name: 'queue-details',
      script: 'artisan',
      args: 'queue:work --queue=details --sleep=1 --timeout=300 --tries=3',
      interpreter: 'php',
      instances: 5,
      watch: false,
    }
  ]
};
