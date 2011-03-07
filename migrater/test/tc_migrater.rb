require 'test/unit'
require 'rubygems'
require 'mocha'
require 'migrater'

class MigraterTestCase < Test::Unit::TestCase

  def setup
    (@db = mock('database')).stubs(:current_schema_version).returns(7)
    @migrations = mock('migrations')
    @migrater = Migrater.new(@migrations, @db)
  end

  def test_should_migrate_to_newest
    @migrations.stubs(:newest).returns(9)
    @migrations.expects(:load_between).with(8, 9).returns([mig8 = mock('mig8'), mig9 = mock('mig9')])
    mig8.expects(:up).with(@db)
    mig9.expects(:up).with(@db)
    @db.expects(:update_schema_version).with(9)
    @migrater.migrate_to_newest
  end

  def test_should_run_up_migrations_when_version_lower_than_target
    @migrations.expects(:load_between).with(8, 10).returns([mig8 = mock('mig8'), mig9 = mock('mig9'), mig10 = mock('mig10')])
    mig8.expects(:up).with(@db)
    mig9.expects(:up).with(@db)
    mig10.expects(:up).with(@db)
    @db.expects(:update_schema_version).with(10)
    @migrater.migrate_to(10)
  end

  def test_should_run_down_migrations_when_version_higher_than_target
    @migrations.expects(:load_between).with(7, 6).returns([mig7 = mock('mig6'), mig6 = mock('mig5')])
    mig7.expects(:down).with(@db)
    mig6.expects(:down).with(@db)
    @db.expects(:update_schema_version).with(5)
    @migrater.migrate_to(5)
  end
  
  def test_should_run_no_migrations_when_target_is_version
    @migrater.migrate_to(7)
  end

end