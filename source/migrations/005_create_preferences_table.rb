class CreatePreferencesTable
  def up(db)
    db.query <<-SQL
CREATE TABLE Preferences (
  account_id        MEDIUMINT UNSIGNED           NOT NULL,
  name              VARCHAR(255)                 NOT NULL,
  value             VARCHAR(255),
  
  PRIMARY KEY (account_id, name)
);
SQL
  end
  
  def down(db)
    db.query "DROP TABLE IF EXISTS Preferences"
  end
  
end