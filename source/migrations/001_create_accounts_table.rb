class CreateAccountsTable
  def up(db)
    db.query <<-SQL
CREATE TABLE Accounts (
  id                INT UNSIGNED         NOT NULL AUTO_INCREMENT,
  username          VARCHAR(255)         NOT NULL,
  hashed_password   VARCHAR(255)         NOT NULL,
  random_salt       VARCHAR(255)         NOT NULL,
  updated_at        TIMESTAMP,
  created_at        TIMESTAMP,
  PRIMARY KEY (id)
);
SQL
  end
  
  def down(db)
    db.query "DROP TABLE IF EXISTS Accounts"
  end

end
