class Migrations
  def initialize(folder)
    @folder = folder
  end
  
  def load_between(from, to)
    numbers_between(from, to).map { |number| load(filename_for(number)) }
  end
  
  def load(file)
    require "#{@folder}/#{file}"
    eval("#{class_name_for(file)}.new")
  end

  def filename_for(number)
    Dir.glob("#{@folder}/#{number.to_s.rjust(3, "0")}_*.rb").first.split("/").last
  end
  
  def newest
    Dir.glob("#{@folder}/*.rb").map { |file| file.split("/").last[0..2] }.select { |num| num.to_i > 0 }.max.to_i
  end
  
  private

  def numbers_between(from, to)
    numbers = []
    from < to ? from.upto(to) { |n| numbers << n } : from.downto(to) { |n| numbers << n }
    numbers
  end

  def class_name_for(file)
    snake_to_capitalized(file[4..-4])
  end
  
  def snake_to_capitalized(snake)
    snake.split("_").map { |word| word.capitalize }.join
  end
  
end