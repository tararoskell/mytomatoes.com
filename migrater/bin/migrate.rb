require 'rubygems'
require 'mysql'
require 'yaml'
require 'migrations'
require 'migrater'
require 'database'

server = ARGV[0] || "local"
migrations = Migrations.new("../source/migrations/")
dbconfig = YAML::load(File.open("config/#{server}.yml"))
connection = Mysql.new(dbconfig["host"], dbconfig["username"], dbconfig["password"], dbconfig["database"])
database = Database.new(connection)

migrater = Migrater.new(migrations, database)

if ARGV[1] === "reset"
  connection.query("DROP DATABASE #{dbconfig["database"]}")
  connection.query("CREATE DATABASE #{dbconfig["database"]}")
  connection.select_db(dbconfig["database"])
  ARGV[1] = false
end

if ARGV[1]
  migrater.migrate_to(ARGV[1].to_i)
else
  migrater.migrate_to_newest
end

connection.close
