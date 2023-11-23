#!/bin/bash

# Specify the directory where home folders are located
home_directory="/home"

# Iterate through the subdirectories in the home directory
for user_home in "$home_directory"/*; do
    if [ -d "$user_home" ]; then
        # Extract the username from the home folder path
        username=$(basename "$user_home")

        # Generate Borgmatic configuration file for the user
        sudo borgmatic config generate --destination "/etc/borgmatic.d/${username}.yaml"
    fi
done
