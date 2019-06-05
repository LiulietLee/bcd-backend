#include <iostream>
#include <cstdio>
#include <cstring>
#include <cmath>
#include <vector>
#include <queue>
#include <set>
#include <map>
#include <string>
#include <algorithm>

int main() {
    freopen("covers.txt", "r", stdin);
    freopen("sql_covers.sql", "w", stdout);

    string strid, title, author, url;
    while (getline(cin, strid)) {
        getline(cin, title); getline(cin, author); getline(cin, url);
        cout << "insert into `cover` (`strid`, `url`, `url`, `title`, `author`) values (\'" << strid << "\', \'" << url << "\', \'" << title << "\', \'" << author << "\');" << endl;
        getline(cin, strid);
    }
 
    fclose(stdin);
    fclose(stdout);

    return 0;
}
