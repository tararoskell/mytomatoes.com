class Database
  def initialize(connection)
    @connection = connection
    puts "---------------------------------------------------------------------"
  end
  
  def current_schema_version
    create_schema_info_table unless @connection.list_tables.include? "schema_info"
    @connection.query("SELECT version FROM schema_info").fetch_row.first.to_i
  end
  
  def update_schema_version(version)
    @connection.query("UPDATE schema_info SET version = #{version}")
  end
  
  def query(sql)
    puts sql
    puts ""
    ret = @connection.query(sql)
    puts "---------------------------------------------------------------------"
    ret
  end
  
  def insert(sql)
    raise "must be insert statement, was \"#{sql}\"" unless sql[0..5] === "INSERT"
    query(sql)
    @connection.query("SELECT LAST_INSERT_ID()").fetch_row.first.to_i
  end
  
  private
  
  def create_schema_info_table
    @connection.query("CREATE TABLE schema_info (version INT, PRIMARY KEY (version));")
    @connection.query("INSERT INTO schema_info VALUES (0);")
  end
  
end