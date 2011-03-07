class Migrater
  def initialize(migrations, database)
    @migrations, @database = migrations, database
  end
  
  def migrate_to(target)
    version = @database.current_schema_version
    if version < target
      @migrations.load_between(version + 1, target).each { |migration| migration.up(@database) }    
    elsif version > target
      @migrations.load_between(version, target + 1).each { |migration| migration.down(@database) }
    else
      puts "Database schema version was equal to target, no migrations necessary."
      return
    end
    @database.update_schema_version target
  end
  
  def migrate_to_newest
    migrate_to(@migrations.newest)
  end
  
end