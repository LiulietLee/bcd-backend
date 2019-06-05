#include <iostream>
#include <string>
using namespace std;

void escapeString(string &str) {
    string tstr = "";
    for (int i = 0; i < str.length(); i++) {
        if (str[i] != '\'') {
            tstr += str[i];
            if (str[i] == '\\') {
                tstr += "\\";
            }
        }
    }
    str = tstr;
}

int main() {
    freopen("covers.txt", "r", stdin);
    freopen("sql_covers.sql", "w", stdout);

    string strid, title, author, url;
    while (getline(cin, strid)) {
        getline(cin, title); getline(cin, author); getline(cin, url);
        escapeString(strid);
        escapeString(title);
        escapeString(author);
        escapeString(url);
        cout << "insert into `cover` (`strid`, `url`, `title`, `author`) values (\'" << strid << "\', \'" << url << "\', \'" << title << "\', \'" << author << "\');" << endl;
        getline(cin, strid);
    }
 
    fclose(stdin);
    fclose(stdout);

    return 0;
}
