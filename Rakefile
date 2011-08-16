require 'rake/clean'

APP_DIR = 'namodgApp'
FILES = FileList.new('**/**') do |fl|
  file_name = File.basename(__FILE__)
  fl.exclude(file_name)
  fl.exclude(File.dirname(file_name))
end

CLEAN.include(APP_DIR)
CLOBBER.include(APP_DIR + '.zip')

task :default => :zip

desc 'Generate a ready-to-deploy build'
task :build do
  mkdir APP_DIR
  FILES.each do |f|
    if File.directory? f
      mkdir File.join(APP_DIR, f)
    else
      cp f, File.join(APP_DIR, File.dirname(f))
    end
  end
end

desc 'Zip the build with the best compression option'
task :zip => :build do
  `zip -r -9 #{APP_DIR}.zip #{APP_DIR}`
end
