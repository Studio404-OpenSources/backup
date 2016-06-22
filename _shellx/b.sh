backup_files="$1";
dest="$2";
archive_folder="$3";
archive_file="$4";

chmod 0777 "$dest";
mkdir "$dest/$archive_folder";
tar czf "$dest/$archive_folder/$archive_file" "$backup_files";
chmod 0755 "$dest";
