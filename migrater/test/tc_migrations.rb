require 'test/unit'
require 'migrations'

class MigrationsTestCase < Test::Unit::TestCase

  def setup
    File.open("001_test_migration_one.rb", "w") { |file| file.puts(first_test_migration) }
    File.open("002_test_migration_two.rb", "w") { |file| file.puts(second_test_migration) }    
    @migrations = Migrations.new(".")
  end
  
  def teardown
    File.delete("001_test_migration_one.rb") if File.exists? "001_test_migration_one.rb"
    File.delete("002_test_migration_two.rb") if File.exists? "002_test_migration_two.rb"
  end

  def test_should_load_between_numbers
    first, second = @migrations.load_between(1, 2)
    assert_equal("first up", first.up)
    assert_equal("second up", second.up)
  end

  def test_should_load_between_decreasing_number
    second, first = @migrations.load_between(2, 1)
    assert_equal("second down", second.down)
    assert_equal("first up", first.up)
  end

  def test_should_find_filename_for_migration_numbers
    assert_equal("001_test_migration_one.rb", @migrations.filename_for(1))
  end

  def test_should_load_a_migration
    migration = @migrations.load("001_test_migration_one.rb")
    assert_equal("first up", migration.up)
    assert_equal("first down", migration.down)
  end

  def test_should_find_newest
    assert_equal(2, @migrations.newest)
  end

  private 
  
  def first_test_migration
    <<-MIGRATION
class TestMigrationOne
  def up
    "first up"
  end
  def down
    "first down"
  end
end
MIGRATION
  end

  def second_test_migration
    <<-MIGRATION
class TestMigrationTwo
  def up
    "second up"
  end
  def down
    "second down"
  end
end
MIGRATION
  end
  

end