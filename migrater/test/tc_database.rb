require 'test/unit'
require 'rubygems'
require 'mocha'
require 'mysql'
require 'database'

class DatabaseTestCase < Test::Unit::TestCase

  def setup
    @my = Mysql.new('localhost', 'root', 'roNNy')
    drop_test_db_if_exists
    @my.query("create database testdb")
    @my.select_db("testdb")
    @database = Database.new(@my)
  end
  
  def teardown
    drop_test_db_if_exists
    @my.close
  end

  def test_should_know_current_schema_version
    create_schema_info
    assert_equal(7, @database.current_schema_version)
  end

  def test_should_update_schema_version
    create_schema_info
    @database.update_schema_version(13)
    assert_equal(13, @my.query("SELECT version FROM schema_info").fetch_row.first.to_i)
  end
  
  def test_should_create_schema_version_if_non_existant
    assert_equal(0, @database.current_schema_version)
    assert(@my.list_tables.include?("schema_info"), "Should have created table schema_info")
  end
  
  def test_should_forward_query
    database = Database.new(conn = mock('connection'))
    conn.expects(:query).with("SELECT * FROM Test").returns("OK")
    assert_equal("OK", database.query("SELECT * FROM Test"))
  end
  
  def test_should_insert_and_return_id
    @my.query("CREATE TABLE testid (id INT NOT NULL AUTO_INCREMENT, var INT, PRIMARY KEY (id));")
    assert_equal(1, @database.insert("INSERT INTO testid (var) VALUES (3)"))
    assert_raise(RuntimeError) { @database.insert("SELECT var FROM testid") }
  end

  private
  
  def create_schema_info
    @my.query("CREATE TABLE schema_info (version INT, PRIMARY KEY (version));")
    @my.query("INSERT INTO schema_info VALUES (7);")
  end
  
  def drop_test_db_if_exists
    @my.query("drop database testdb") if @my.list_dbs.include? "testdb"
  end
  
end