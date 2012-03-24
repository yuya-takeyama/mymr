guard 'phpunit', :tests_path => 'tests', :cli => '--colors' do
  watch(%r{^.+Test\.php$})
  watch(%r{src/MyMR/(.*).php$}) {|m| "tests/MyMR/Tests/#{m[1]}Test.php" }
end
