#!/usr/bin/env ruby

require 'rubygems'
require 'gearman'
require 'json'

servers = ['localhost:4730']
worker = Gearman::Worker.new(servers)

# Add a handler for the "reverse" task
worker.add_ability('reverse') do |data,job|
  data = JSON.parse data
  puts "Received job: #{job.handle()}"
  puts data['data'].reverse
end
loop { worker.work }
